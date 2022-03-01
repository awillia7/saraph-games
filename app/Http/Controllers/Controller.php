<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $defaults = [
        'limit' => 250,
        'page' => 0
    ];

    protected function add_meta_data($collection, $request)
    {
        return $collection->merge([
            'path' => $request->getPathInfo(),
            'auth' => Auth::check()
        ]);
    }

    protected function parse_includes(array $includes)
    {
        $return = [
            'includes' => [],
            'modes' => []
        ];

        foreach ($includes as $include) {
            $explode = explode(':', $include);

            if(!isset($explode[1])) {
                $explode[1] = $this->defaults['mode'];
            }

            $return['includes'][] = $explode[0];
            $return['modes'][$explode[0]] = $explode[1];
        }

        return $return;
    }

    protected function parse_sort(array $sort)
    {
        return array_map(function($sort) {
            if (!isset($sort['direction'])) {
                $sort['direction'] = 'asc';
            }

            return $sort;
        }, $sort);
    }

    protected function parse_filter_groups(array $filter_groups)
    {
        $return = [];

        foreach($filter_groups as $group) {
            if (!array_key_exists('filters', $group)) {
                // ADD ERROR FOR FILTER KEY
            }

            $filters = array_map(function($filter) {
                if (!isset($filter['not'])) {
                    $filter['not'] = false;
                }

                return $filter;
            }, $group['filters']);

            $return[] = [
                'filters' => $filters,
                'or' => isset($group['or']) ? $group['or'] : false
            ];
        }

        return $return;
    }

    protected function parse_resource_options($request = null)
    {
        if ($request == null) {
            $request = request();
        }

        $this->defaults = array_merge([
            'includes' => [],
            'sort' => [],
            'limit' => null,
            'page' => null,
            'mode' => 'embed',
            'filter_groups' => []
        ], $this->defaults);

        $includes = $this->parse_includes($request->get('includes', $this->defaults['includes']));
        $sort = $this->parse_sort($request->get('sort', $this->defaults['sort']));
        $limit = $request->get('limit', $this->defaults['limit']);
        $page = $request->get('page', $this->defaults['page']);
        $filter_groups = $this->parse_filter_groups($request->get('filter_groups', $this->defaults['filter_groups']));
        
        if ($page !== null && $limit === null) {
            // NEED TO ADD ERROR MESSAGE
        }

        return [
            'includes' => $includes['includes'],
            'modes' => $includes['modes'],
            'sort' => $sort,
            'limit' => $limit,
            'page' => $page,
            'filter_groups' => $filter_groups
        ];
    }

    protected function has_custom_method($type, $key)
    {
        $methodName = sprintf('%s%s', $type, Str::studly($key));
        if (method_exists($this, $methodName)) {
            return $methodName;
        }

        return false;
    }
    protected function apply_filter(Builder $queryBuilder, array $filter, $or = false, array &$joins)
    {
        // Destructure Shorthand FIltering Syntax if filter is Shorthand
        if(!array_key_exists('key', $filter) && count($filter) >= 3) {
            $filter = [
                'key'       => ($filter[0] ?: null),
                'operator'  => ($filter[1] ?: null),
                'value'     => ($filter[2] ?: null),
                'not'       => (array_key_exists(3, $filter) ? $filter[3] : null),
            ];
        }

        // $value, $not, $key, $operator
        extract($filter);

        $dbType = $queryBuilder->getConnection()->getDriverName();

        $table = $queryBuilder->getModel()->getTable();

        if ($value === 'null' || $value === '') {
            $method = $not ? 'WhereNotNull' : 'WhereNull';

            call_user_func([$queryBuilder, $method], sprintf('%s.%s', $table, $key));
        } else {
            $method = filter_var($or, FILTER_VALIDATE_BOOLEAN) ? 'orWhere' : 'where';
            $clauseOperator = null;
            $databaseField = null;

            switch($operator) {
                case 'ct':
                case 'sw':
                case 'ew':
                    $valueString = [
                        'ct' => '%'.$value.'%',  // contains
                        'ew' => '%'.$value, // ends with
                        'sw' => $value.'%' // starts with
                    ];

                    $castToText = (($dbType === 'postgress') ? 'TEXT' : 'CHAR');
                    $databaseField = DB::raw(sprintf('CAST(%s.%s AS ' . $castToText . ')', $table, $key));
                    $clauseOperator = ($not ? 'NOT':'') . (($dbType === 'postgres') ? 'ILIKE' : 'LIKE');
                    $value = $valueString[$operator];
                    break;
                case 'eq':
                default:
                    $clauseOperator = $not ? '!=' : '=';
                    break;
                case 'gt':
                    $clauseOperator = $not ? '<' : '>';
                    break;
                case 'gte':
                    $clauseOperator = $not ? '<' : '>=';
                    break;
                case 'lte':
                    $clauseOperator = $not ? '>' : '<=';
                    break;
                case 'lt':
                    $clauseOperator = $not ? '>' : '<';
                    break;
                case 'in':
                    if ($or === true) {
                        $method = $not === true ? 'orWhereNotIn' : 'orWhereIn';
                    } else {
                        $method = $not === true ? 'whereNotIn' : 'whereIn';
                    }
                    $clauseOperator = false;
                    break;
                case 'bt':
                    if ($or === true) {
                        $method = $not === true ? 'orWhereNotBetween' : 'orWhereBetweeen';
                    } else {
                        $method = $not === true ? 'whereNotBetween' : 'whereBetween';
                    }
                    $clauseOperator = false;
                    break;
            }

            if (is_null($databaseField)) {
                $databaseField = sprintf('%s.%s', $table, $key);
            }

            $customFilterMethod = $this->has_custom_method('filter', $key);
            if ($customFilterMethod) {
                call_user_func_array([$this, $customFilterMethod], [
                    $queryBuilder,
                    $method,
                    $clauseOperator,
                    $value
                ]);
                
                // column to join
                // trying to join within a nested where will get the join ignored
                $joins[] = $key;
            } else {
                // In operations do not have an operator
                if (in_array($operator, ['in', 'bt'])) {
                    call_user_func_array([$queryBuilder, $method], [
                        $databaseField, $value
                    ]);
                } else {
                    call_user_func_array([$queryBuilder, $method], [
                        $databaseField, $clauseOperator, $value
                    ]);
                }
            }
        }
    }

    protected function apply_filter_groups(Builder $queryBuilder, array $filterGroups = [], array $previouslyJoined = [])
    {
        $joins = [];
        foreach ($filterGroups as $group) {
            $or = $group['or'];
            $filters = $group['filters'];

            $queryBuilder->where(function (Builder $query) use ($filters, $or, &$joins) {
                foreach ($filters as $filter) {
                    $this->apply_filter($query, $filter, $or, $joins);
                }
            });
        }

        foreach(array_diff($joins, $previouslyJoined) as $join) {
            $this->joinRelatedModelIfExists($queryBuilder, $join);
        }

        return $joins;
    }

    protected function apply_sorting(Builder $queryBuilder, array $sorting, array $previouslyJoined = [])
    {
        $joins = [];
        foreach($sorting as $sortRule) {
            if (is_array($sortRule)) {
                $key = $sortRule['key'];
                $direction = mb_strtolower($sortRule['direction']) === 'asc' ? 'ASC' : 'DESC';
            } else {
                $key = $sortRule;
                $direction = 'ASC';
            }

            $customSortMethod = $this->has_custom_method('sort', $key);
            if ($customSortMethod) {
                $joins[] = $key;

                call_user_func([$this, $customSortMethod], $queryBuilder, $direction);
            } else {
                $queryBuilder->orderBy($key, $direction);
            }
        }

        foreach(array_diff($joins, $previouslyJoined) as $join) {
            $this->joinRelatedModelIfExists($queryBuilder, $join);
        }

        return $joins;
    }

    protected function apply_resource_options(Builder $queryBuilder, array $options = [])
    {
        if (empty($options)) {
            return $queryBuilder;
        }

        extract($options);

        if (isset($includes)) {
            if (!is_array($includes)) {
                // NEED TO ADD ERROR BECUASE INCLUDE MUST BE ARRAY
            }

            $queryBuilder->with($includes);
        }

        if (isset($filter_groups)) {
            $filterJoins = $this->apply_filter_groups($queryBuilder, $filter_groups);
        }

        if (isset($sort)) {
            if (!is_array($sort)) {
                // THROW ERROR BECAUSE sort MUST BE ARRAY
            }

            if (!isset($filterJoins)) {
                $filterJoins = [];
            }

            $sortingJoins = $this->apply_sorting($queryBuilder, $sort, $filterJoins);
        }

        if (isset($limit)) {
            $queryBuilder->limit($limit);
        }

        if (isset($page)) {
            if (!isset($limit)) {
                // THROW ERROR BECAUSE page REQUIRES limit
            }

            $queryBuilder->offset($page * $limit);
        }

        if (isset($distinct)) {
            $queryBuilder->distinct();
        }

        return $queryBuilder;
    }
}

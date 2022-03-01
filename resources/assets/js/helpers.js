import animateScroll from 'scrollto-with-animation';

let populateCard = function(state) {
    if (!state) return {};
    let obj = {
        id: state.id,
        title: state.title,
        types: state.types,
        brigades: state.brigades,
        strength: state.strength,
        toughness: state.toughness,
        class: state.class,
        special_ability: state.special_ability,
        identifiers: state.identifiers,
        reference: state.reference,
        artist: state.artist,
        rarity: state.rarity,
        play_as: state.play_as,
        errata: state.errata,
        set: state.set,
        image: state.image
    };

    return obj;
}

export { populateCard };

let buildQueryGroup = function(field, text, groupIndex) {
    let words = text.split(" ");
    let queryGroup = ``;
    let value = "";
    
    for (let i = 0; i < words.length; i++) {
        value = words[i];
        if (i > 0) {
            queryGroup += "&";
        }

        queryGroup += `&filter_groups[${groupIndex}][filters][${i}][key]=${field}&filter_groups[${groupIndex}][filters][${i}][value]=${value}&filter_groups[${groupIndex}][filters][${i}][operator]=ct`
    }

    return queryGroup;
}

export { buildQueryGroup };

export const scrollTo = (pos, duration = 600, delay = 0) => new Promise(resolve => {
    setTimeout(() => {
        animateScroll(document.documentElement, 'scrollTop', pos, duration, 'easeInOutSine', resolve)
    }, delay)
});

export const kebabify = (words) =>
    words
        .toLowerCase()
        .replace(' ', '-');

export const prettyDate = (date) =>
    new Date(date)
        .toString()
        .split(' ')
        .splice(0, 4)
        .join(' ')
        .replace(/( \d+)$/, ',$1');

let populatePostSummary = function(state) {
    if (!state) return {};
    
    let obj = {
        id: state.id,
        title: state.title,
        key: state.key,
        image: state.image,
        author: state.author,
        published: prettyDate(state.published)
    };

    return obj;
}

export { populatePostSummary };

let populatePost = function(state) {
    if (!state) return {};
        
    let obj = {
        id: state.id,
        title: state.title,
        key: state.key,
        image: state.image,
        content: state.content,
        author: state.author,
        published: prettyDate(state.published),
        description: state.description
    };

    return obj;
}

export { populatePost };

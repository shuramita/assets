
exports.combine = function(mix){
    let source = `${__dirname}/src`;
    let dest = 'public';
    mix.sass(`${source}/assets/sass/asset.scss`, `${dest}/css/assets/asset.css`).version();
    mix.js([
        `${source}/assets/js/asset.js`
    ], `${dest}/js/asset/asset.js`).version();
};

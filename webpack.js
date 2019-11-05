
exports.combine = function(mix){
    let source = `${__dirname}/src`;
    let dest = 'public';
    let package = 'asset';
    mix.sass(`${source}/assets/sass/asset.scss`, `${dest}/css/assets/asset.css`);
    mix.js([
        `${source}/assets/js/asset.js`
    ], `${dest}/js/asset/asset.js`);
    mix.copyDirectory(`${source}/assets/svg`,`resources/svg/vendor/${package}`);
};

const fs = require('fs');
exports.combine = function(mix){
    mix.webpackConfig(
        {
            resolve: {
                alias: {
                    '@asset': __dirname + '/src/assets'
                }
            }
        }
    );
    let source = `${__dirname}/src`;
    let dest = 'public';
    let package = 'asset';
    mix.sass(`${source}/assets/sass/asset.scss`, `${dest}/css/assets/asset.css`);
    mix.copy(`${source}/assets/js/router.js`, `${dest}/routers/asset-router.js`);
    fs.copyFileSync(`${source}/assets/js/router.js`, `${dest}/routers/asset-router.js`);
    mix.copyDirectory(`${source}/assets/svg`,`resources/svg/vendor/${package}`);
};

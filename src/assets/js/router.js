import AssetItem from '@asset/js/components/item/Item';
import AssetSetting from '@asset/js/components/setting/Setting';
import Auth from "@backoffice/js/services/Auth";

const assetRoutes = [
    {path: '/asset/items', component: AssetItem, beforeEnter: Auth.requireAuth, name: 'asset.items'},
    {path: '/asset/setting', component: AssetSetting, beforeEnter: Auth.requireAuth, name: 'asset.setting'}
];

export default assetRoutes;

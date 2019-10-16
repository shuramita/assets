import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { HttpParams } from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})

export class AssetService extends BaseService {
    static SERVICE_PREFIX = 'asset';
    static SYSTEM_INFO = 'system-info';
    static BUILDING_INFO = 'building-info';
    static ORGANIZATION_DETAIL = 'organization';
    static SEARCH = 'search';
    static ADD_ASSET = 'add';
    static ASSET_DETAIL = 'info';
    static UPDATE_ASSET = 'update';
    static ADD_FLOOR = 'floor/add';
    static ADD_FIELD = 'field/add';
    static ADD_BUILDING = 'building/add';
    static ADD_ORGANIZATION = 'organization/add';
    static ADD_PRICE = 'price/add';

    getSystemInfo() {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.SYSTEM_INFO]);
        return this.get(path);
    }

    getBuildingInfo(id: number) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.BUILDING_INFO, id]);
        return this.get(path);
    }

    getOrganizationDetail(id: number) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ORGANIZATION_DETAIL, id]);
        return this.get(path);
    }

    getAssets(params?: HttpParams | any) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.SEARCH]);
        return this.get(path, params);
    }

    getAssetDetail(id: number) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ASSET_DETAIL, id]);
        return this.get(path);
    }

    postAddAsset(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_ASSET]);
        return this.post(path, body);
    }

    putUpdateAsset(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.UPDATE_ASSET]);
        return this.post(path, body);
    }

    postAddFloor(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_FLOOR]);
        return this.post(path, body);
    }

    postAddField(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_FIELD]);
        return this.post(path, body);
    }

    postAddBuilding(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_BUILDING]);
        return this.post(path, body);
    }

    postAddOrganization(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_ORGANIZATION]);
        return this.post(path, body);
    }

    postAddPrice(body) {
        const path = BaseService.mapPaths([AssetService.SERVICE_PREFIX, AssetService.ADD_PRICE]);
        return this.post(path, body);
    }
}

import {Building} from '@app/models/global-data';

export interface Asset {
    id: number;
    name: string;
    size: string;
    location: string;
    floor: string;
    type: string;
    price: string;
    description?: string;
    displayDescription?: string;
    building?: Building
}

export class AssetModel implements Asset {
    id: number;
    name: string;
    size: string;
    location: string;
    floor: string;
    type: string;
    price: string;

    constructor(assetObject: Asset) {
        this.id = assetObject.id;
        this.name = assetObject.name;
        this.size = assetObject.size;
        this.location = assetObject.location;
        this.floor = assetObject.floor;
        this.type = assetObject.type;
        this.price = assetObject.price;
    }
}

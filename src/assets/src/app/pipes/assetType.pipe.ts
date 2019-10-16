import { Pipe, PipeTransform } from '@angular/core';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';

@Pipe({
    name: 'assetTypePipe'
})
export class AssetTypePipe implements PipeTransform {
    private globalData: GlobalData;
    private data;

    constructor(private globalService: GlobalService) {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.asset_types;
        });
    }

    transform(itemId: any): any {
        const result = this.data.find((item) => {
            return item.id === itemId;
        });

        return result.name;
    }
}

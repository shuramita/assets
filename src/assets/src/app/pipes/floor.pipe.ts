import { Pipe, PipeTransform } from '@angular/core';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';

@Pipe({
    name: 'floorPipe'
})
export class FloorPipe implements PipeTransform {
    private globalData: GlobalData;
    private floorData;

    constructor(private globalService: GlobalService) {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.floorData = this.globalData.organization.building.floors;
        });
    }

    transform(itemId: any): any {
        const result = this.floorData.find((item) => {
            return item.id === itemId;
        });

        return result ? result.name : '';
    }
}

import { Pipe, PipeTransform } from '@angular/core';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';

@Pipe({
    name: 'pricePipe'
})
export class PricePipe implements PipeTransform {
    private globalData: GlobalData;
    private data;

    constructor(private globalService: GlobalService) {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.organization.prices;
        });
    }

    transform(priceId: any): any {
        const result = this.data.find((price) => {
            return price.id === priceId;
        });

        return result ? result.name : '';
    }
}

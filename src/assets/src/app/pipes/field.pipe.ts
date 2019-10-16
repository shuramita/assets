import { Pipe, PipeTransform } from '@angular/core';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';

@Pipe({
    name: 'fieldPipe'
})
export class FieldPipe implements PipeTransform {
    private globalData: GlobalData;
    private data;

    constructor(private globalService: GlobalService) {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.organization.fields;
        });
    }

    transform(fieldId: any): any {
        const result = this.data.find((field) => {
            return field.id === fieldId;
        });

        return result ? result.title : '';
    }
}

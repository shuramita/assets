import { Component, OnInit } from '@angular/core';
import { AssetService } from '@service/asset.service';
import { GlobalService } from '@service/global.service';
import { Field, GlobalData, Price } from '@app/models/global-data';
import { MatDialog, MatDialogConfig } from '@angular/material';
import { PriceTypeAddComponent } from '@app/components/modals/add-price-type/price-type-add.component';
import { CustomFieldSettingAddComponent } from '@app/components/modals/custom-field-setting-add/custom-field-setting-add.component';

@Component({
    selector: 'app-custom-field-setting',
    templateUrl: './custom-field-setting.component.html',
    styleUrls: ['./custom-field-setting.component.scss']
})
export class CustomFieldSettingComponent implements OnInit {
    globalData: GlobalData;
    data: Array<Field>;
    displayedColumns = [
        'id',
        'key',
        'title',
        'type',
        'description',
        'more'
    ];
    addCustomFieldDialogConfig: MatDialogConfig;

    constructor(private assetService: AssetService,
        private globalService: GlobalService,
        private _matDialog: MatDialog) {
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.organization.fields;
        });
        this.addCustomFieldDialogConfig = {
            id: 'add-custom-field',
            panelClass: 'add-custom-field',
            width: '30%',
            disableClose: true,
            autoFocus: false
        };
    }

    loadData() {
        this.data = [];
        this.assetService.getSystemInfo().subscribe(res => {
            this.globalService.addGlobalData(res);
        });
    }

    openAddNewPriceDialog() {
        const dialogRef = this._matDialog.open(CustomFieldSettingAddComponent, this.addCustomFieldDialogConfig);
        dialogRef.afterClosed().subscribe(() => {
            this.loadData();
        });
    }
}

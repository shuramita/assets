import { Component, OnInit } from '@angular/core';
import { AssetService } from '@service/asset.service';
import { GlobalService } from '@service/global.service';
import { GlobalData, Price } from '@app/models/global-data';
import { MatDialog, MatDialogConfig } from '@angular/material';
import { PriceTypeAddComponent } from '@app/components/modals/add-price-type/price-type-add.component';

@Component({
    selector: 'app-price-setting',
    templateUrl: './price-setting.component.html',
    styleUrls: ['./price-setting.component.scss']
})
export class PriceSettingComponent implements OnInit {
    globalData: GlobalData;
    data: Array<Price>;
    displayedColumns = [
        'id',
        'name',
        'type',
        'unit',
        'more'
    ];
    addPriceDialogConfig: MatDialogConfig;

    constructor(private assetService: AssetService,
        private globalService: GlobalService,
        private _matDialog: MatDialog) {
    }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.organization.prices;
        });
        this.addPriceDialogConfig = {
            id: 'add-price',
            panelClass: 'add-price',
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
        const dialogRef = this._matDialog.open(PriceTypeAddComponent, this.addPriceDialogConfig);
        dialogRef.afterClosed().subscribe(() => {
            this.loadData();
        });
    }
}

import { Component, OnInit } from '@angular/core';
import { AssetService } from '@service/asset.service';
import { GlobalService } from '@service/global.service';
import { GlobalData, Price } from '@app/models/global-data';
import { MatDialog, MatDialogConfig } from '@angular/material';
import { PriceTypeAddComponent } from '@app/components/modals/add-price-type/price-type-add.component';

@Component({
    selector: 'app-setting',
    templateUrl: './setting.component.html',
    styleUrls: ['./setting.component.scss']
})
export class SettingComponent implements OnInit {

    constructor() {
    }

    ngOnInit() {
    }
}

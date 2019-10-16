import { Component, OnInit, ViewChild } from '@angular/core';
import { AssetService } from '@service/asset.service';
import { GlobalService } from '@service/global.service';
import { GlobalData } from '@app/models/global-data';
import { MatButton } from '@angular/material';
import { IMAGE_URL } from '@app/shared/common/common.constant';

@Component({
    selector: 'app-asset-list',
    templateUrl: './asset-list.component.html',
    styleUrls: ['./asset-list.component.scss']
})
export class AssetListComponent implements OnInit {
    @ViewChild('buttonAddNew') buttonAddNew: MatButton;
    buttonAddNewWidth;
    globalData: GlobalData;
    displayedColumns: string[] = [
        'id',
        'cover',
        'name',
        'size',
        'building',
        'floor',
        'type',
        'price',
        'status',
        'more'
    ];
    dataSource: any;
    page = {
        pageIndex: 0,
        length: 0,
        pageSize: 15
    };

    constructor(private assetService: AssetService,
        private globalService: GlobalService) { }

    ngOnInit() {
        this.reloadData(this.page);
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
        });
    }

    reloadData(event) {
        const currentPage = {
            page: event.pageIndex + 1
        };
        this.assetService.getAssets(currentPage).subscribe(res => {
            const data = [];
            let assetInfo = {};
            res.data.forEach((asset) => {
                const firstNormalPrice = asset.prices.find((price) => price.type === 'normal');
                const priceDisplay = firstNormalPrice ? firstNormalPrice.pivot.price + ' - ' + firstNormalPrice.unit : '0';
                assetInfo = {
                    'id': asset.id,
                    'cover': asset.cover ? 'http://demo.realestatedoc.co/' + asset.cover.values.medium.uri : IMAGE_URL.NO_FLOORPLAN,
                    'name': asset.name,
                    'size': asset.size.value + ' ' + asset.size.unit,
                    'building': asset.building.name,
                    'floor_id': asset.floor_id,
                    'asset_type_id': asset.asset_type_id,
                    'price': priceDisplay,
                    'status': asset.status
                };

                data.push(assetInfo);
            });
            this.dataSource = data;
            this.page = {
                pageIndex: res.current_page - 1,
                length: res.total,
                pageSize: res.per_page,
            };
        });
    }

    onMenuOpen() {
        this.buttonAddNewWidth = this.buttonAddNew._elementRef.nativeElement.clientWidth;
    }

    getMenuAddNewWidth() {
        return this.buttonAddNewWidth ? this.buttonAddNewWidth + 'px' : 'auto';
    }
}

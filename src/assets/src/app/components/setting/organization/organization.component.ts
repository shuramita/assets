import { Component, OnInit } from '@angular/core';
import {GlobalData, Organization} from '@app/models/global-data';
import {AssetService} from '@service/asset.service';
import {GlobalService} from '@service/global.service';

@Component({
  selector: 'app-organization',
  templateUrl: './organization.component.html',
  styleUrls: ['./organization.component.scss']
})
export class OrganizationComponent implements OnInit {

    globalData: GlobalData;
    data: Array<Organization>;
    displayedColumns = [
        'id',
        'is_working_organization',
        'name',
        'address',
        'timezone',
        'more'
    ];
    constructor(
        private assetService: AssetService,
        private globalService: GlobalService
    ) { }

    ngOnInit() {
        this.globalService.globalData.subscribe(res => {
            this.globalData = res;
            this.data = this.globalData.organizations;
            console.log(this.data);
        });
    }

}

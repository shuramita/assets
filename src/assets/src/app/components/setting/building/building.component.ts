import { Component, OnInit } from '@angular/core';
import {Building, GlobalData} from '@app/models/global-data';
import {GlobalService} from '@service/global.service';
import {AssetService} from '@service/asset.service';

@Component({
  selector: 'app-building',
  templateUrl: './building.component.html',
  styleUrls: ['./building.component.scss']
})
export class BuildingComponent implements OnInit {
    globalData: GlobalData;
    data: Array<Building>;
    displayedColumns = [
        'id',
        'is_working_building',
        'name',
        'address',
        'building_type',
        'more'
    ];
  constructor(
      private assetService: AssetService,
      private globalService: GlobalService
  ) { }

  ngOnInit() {
      this.globalService.globalData.subscribe(res => {
          this.globalData = res;
          this.data = this.globalData.organization.buildings;
          console.log(this.data);
      });
  }

}

import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { VenueService } from '@app/services/venue.service';
import { GlobalService } from '@app/services/global.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-venue-list-booking',
  templateUrl: './venue-list-booking.component.html',
  styleUrls: ['./venue-list-booking.component.scss']
})
export class VenueListBookingComponent implements OnInit {
  buildings = [
    {
      key: 'all',
      value: 'All Buildings'
    }
  ];
  listBookings;
  selectedBuilding;
  _selectingFloorId;
  listBookingsApproval;
  listBookingsPending;
  floors = [];
  building;
  constructor(
    protected venueService: VenueService,
    private globalService: GlobalService,
    private _router: Router
  ) { }

  ngOnInit() {
    this.selectedBuilding = this.buildings[0].key;
   
    this.getListBookings();
    this.getFloors();
  }

  getFloors() {
    this.globalService.globalData.subscribe(res => {
      const globalData = res;
      this.building = globalData.organization.building;
     
      if (this.building.floors.length > 0) {
        this._selectingFloorId = this.building.floors[0].id;
      }
    });
  }

  getListBookings() {
    this.venueService.getBookings()
    .subscribe(res => {
      this.listBookings = res.data;
      this.listBookingsApproval = this.listBookings.filter(x => x.status === 'approved');
      this.listBookingsPending = this.listBookings.filter(x => x.status !== 'approved');
    });
  }

  mouseEnter(id) {
    const ele: any = document.querySelector('.divbtn--' + id);
    ele.style.display = 'block';
  }

  mouseLeave(id) {
    const ele: any = document.querySelector('.divbtn--' + id);
    ele.style.display = 'none';
  }

  onSelectFloor(id?) {
    this._selectingFloorId = id;
  }

  goToDetailPage(orderId) {
    console.log('vo day', orderId)
    this._router.navigate(['asset/venue/order/detail/' + orderId]);
  }
}

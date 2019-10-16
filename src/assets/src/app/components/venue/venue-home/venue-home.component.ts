import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { VenueService } from '@service/venue.service';
import { Asset } from '@app/models/asset';
import { GlobalService } from '@app/services/global.service';
import { IMAGE_URL } from '@app/shared/common/common.constant';
import {HelperService} from '@service/helper.service';
@Component({
  selector: 'app-venue-home',
  templateUrl: './venue-home.component.html',
  styleUrls: ['./venue-home.component.scss']
})
export class VenueHomeComponent implements OnInit {
  numberOfPax = 1;
  eventType;
  venueType;
  dateOfCheckIn = new Date();
  dateOfCheckOut;
  venues: Array<Asset>;
  IMAGE_URL;
  eventTypes = [];
  venueTypes = [];
  constructor(
    private _router: Router,
    protected venueService: VenueService,
    private globalService: GlobalService,
    public helper: HelperService
  ) {
    this.IMAGE_URL = IMAGE_URL;
  }

  ngOnInit() {
    this.venueService.getFeaturedVenue().subscribe(res => {
      this.venues = res.data;

      const limit = 60;
      this.venues.forEach(v => {
          v.displayDescription = v.description;
          if (v.description.length > limit) {
              v.displayDescription = v.description.slice(0, limit) + ' ...';
          }
      });
    });

    const globalData = this.globalService.globalData.value;
    this.venueTypes = globalData.venue_types;
    this.venueType = this.venueTypes[0].id;
    // Object.keys(globalData.events).forEach(k => {
    //   this.eventTypes.push(k);
    // });
    this.eventTypes = globalData.events;
    this.eventType = this.eventTypes['Party'][0].id;
      // console.log(this.eventTypes);
  }

  plusNumberOfPax() {
    return this.numberOfPax++;
  }

  minusNumberOfPax() {
    if (this.numberOfPax > 1) {
      return this.numberOfPax--;
    }
  }

  submitSearch() {
    this._router.navigate(['asset/venue/search'], {
      queryParams: {
        eventType: this.eventType,
        venueType: this.venueType,
        numberOfPax: this.numberOfPax,
        dateOfCheckIn: this.dateOfCheckIn,
        dateOfCheckOut: this.dateOfCheckOut
      }
    });
  }
}

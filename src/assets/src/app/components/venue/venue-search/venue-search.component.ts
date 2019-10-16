import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { VenueService } from '@app/services/venue.service';
import { GlobalService } from '@app/services/global.service';
import { IMAGE_URL } from '@app/shared/common/common.constant';
import {HelperService} from '@service/helper.service';

@Component({
  selector: 'app-venue-search',
  templateUrl: './venue-search.component.html',
  styleUrls: ['./venue-search.component.scss']
})
export class VenueSearchComponent implements OnInit {
  numberOfPax = 1;
  eventType;
  venueType;
  dateOfCheckIn = new Date();
  dateOfCheckOut;

  eventTypes = [];
  venueTypes = [];
  venues = [];
  IMAGE_URL;
  constructor(
    private _route: ActivatedRoute,
    protected venueService: VenueService,
    private globalService: GlobalService,
    public helper: HelperService
  ) { 
    this.IMAGE_URL = IMAGE_URL;
  }

  ngOnInit() {
    this._route.queryParams.subscribe(params => {
      const globalData = this.globalService.globalData.value;
      this.venueTypes = globalData.venue_types;
      this.eventTypes = globalData.events;
      // Object.keys(globalData.events).forEach(k => {
      //   this.eventTypes.push(k);
      // });
      this.eventType = +params['eventType'];
      this.venueType = +params['venueType'];
      this.numberOfPax = +params['numberOfPax'] || 1;
      this.dateOfCheckIn = new Date(params['dateOfCheckIn']);
      this.dateOfCheckOut = new Date(params['dateOfCheckOut']);
      this.search();
    });
  }

  search() {
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
  }

  plusNumberOfPax() {
    return this.numberOfPax++;
  }

  minusNumberOfPax() {
    if (this.numberOfPax > 1) {
      return this.numberOfPax--;
    }
  }
}

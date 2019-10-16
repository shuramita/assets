import { Component, OnInit } from '@angular/core';
import { HelperService } from '@service/helper.service';
import { ActivatedRoute } from '@angular/router';
import { VenueService } from '@app/services/venue.service';
import { IMAGE_URL } from '@app/shared/common/common.constant';

@Component({
  selector: 'app-venue-detail',
  templateUrl: './venue-detail.component.html',
  styleUrls: ['./venue-detail.component.scss']
})
export class VenueDetailComponent implements OnInit {
  venueDetail = {
    name: '1989 Hotel',
    address: '756 Ressie Mission Apt. 807',
    description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&apos;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, Lorem Ipsum has been and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
    amenities: [
      {
        description: 'High-speed Internet',
        icon: 'wifi'
      },
      {
        description: 'Operation time 9AM-6PM, Mon-Sat',
        icon: 'watch_later'
      },
      {
        description: 'Access to Online Community',
        icon: 'people_outline'
      },
      {
        description: 'Printer & Scanner Availabillity',
        icon: 'local_printshop'
      },
      {
        description: 'Guest Reception',
        icon: 'room_service'
      },
      {
        description: 'Office Telephone (Monthly charge)',
        icon: 'call'
      }
    ],
    directions: [
      {
        description: 'Woodward, I-696',
        icon: 'subdirectory_arrow_left'
      },
      {
        description: 'SMART Bus Routes nearby',
        icon: 'directions_bus'
      }
    ]
  };
  venueId;
  venue;
  IMAGE_URL;
  constructor(
    public helper: HelperService,
    protected venueService: VenueService,
    private _route: ActivatedRoute
  ) {
    this.IMAGE_URL = IMAGE_URL;
  }

  ngOnInit() {
    this.venueId = this._route.snapshot.params['venueId'];
    this.venueService.getVenueDetail(this.venueId).subscribe(res => {
      this.venue = res;
    });
  }
}

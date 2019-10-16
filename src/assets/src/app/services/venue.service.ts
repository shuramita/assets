import { Injectable } from '@angular/core';
import {BaseService} from '@service/base.service';

@Injectable({
  providedIn: 'root'
})
export class VenueService extends BaseService {
    static SERVICE_PREFIX = 'asset';
    static FEATURED = 'public/search';
    static DETAIL = 'public/info';
    static BOOKING = 'booking';
    static BOOKING_SEARCH = 'booking/search';
    static BOOKING_REJECT = 'booking/reject';
    static BOOKING_APPROVE = 'booking/approve';
    getFeaturedVenue() {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.FEATURED]);
        return this.get(path);
    }

    getVenueDetail(id: number) {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.DETAIL, id]);
        return this.get(path);
    }

    getBookings() {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.BOOKING_SEARCH]);
        return this.get(path);
    }

    postBooking(body) {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.BOOKING]);
        return this.post(path, body);
    }

    getBookingDetail(id: number) {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.BOOKING, id]);
        return this.get(path);
    }

    postBookingReject(id) {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.BOOKING_REJECT, id]);
        return this.post(path);
    }

    postBookingApprove(id) {
        const path = BaseService.mapPaths([VenueService.SERVICE_PREFIX, VenueService.BOOKING_APPROVE, id]);
        return this.post(path);
    }
}

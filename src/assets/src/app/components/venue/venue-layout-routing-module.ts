import { ModuleWithProviders } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { VenueLayoutComponent } from '@app/components/venue/venue-layout.component';
import { VenueHomeComponent } from '@app/components/venue/venue-home/venue-home.component';
import { VenueDetailComponent } from '@app/components/venue/venue-detail/venue-detail.component';
import { VenueBookingComponent } from '@app/components/venue/venue-booking/venue-booking.component';
import { VenueSearchComponent } from '@app/components/venue/venue-search/venue-search.component';

const routes: Routes = [
    {
        path: '',
        component: VenueLayoutComponent,
        children: [
            {
                path: '',
                component: VenueHomeComponent
            },
            {
                path: 'detail/:venueId',
                component: VenueDetailComponent
            },
            {
                path: 'booking/:venueId',
                component: VenueBookingComponent
            },
            {
                path: 'search',
                component: VenueSearchComponent
            }
        ]
    }
];

export const VenueLayoutRoutingModule: ModuleWithProviders = RouterModule.forChild(
    routes
);

import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VenueLayoutRoutingModule } from '@app/components/venue/venue-layout-routing-module';
import { VenueLayoutComponent } from '@app/components/venue/venue-layout.component';
import { VenueHomeComponent } from './venue-home/venue-home.component';
import { MaterialModule } from '@app/shared/material/material.module';
import { VenueDetailComponent } from './venue-detail/venue-detail.component';
import { VenueBookingComponent } from './venue-booking/venue-booking.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { VenueSearchComponent } from './venue-search/venue-search.component';
import { DateAdapter, MAT_DATE_FORMATS } from '@angular/material';
import { AppDateAdapter, APP_DATE_FORMATS } from '@app/shared/adapters/datepicker.adapter';

@NgModule({
    imports: [
        CommonModule,
        VenueLayoutRoutingModule,
        MaterialModule,
        ReactiveFormsModule,
        FormsModule
    ],
    declarations: [
        VenueLayoutComponent,
        VenueHomeComponent,
        VenueDetailComponent,
        VenueBookingComponent,
        VenueSearchComponent
    ],
    providers: [
        { provide: DateAdapter, useClass: AppDateAdapter },
        {
            provide: MAT_DATE_FORMATS,
            useValue: APP_DATE_FORMATS
        }
    ]
})
export class VenueLayoutModule {
    constructor() {
        // console.log('LayoutSimpleModule');
    }
}

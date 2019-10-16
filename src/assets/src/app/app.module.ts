import { BrowserModule } from '@angular/platform-browser';
import { NgModule, NO_ERRORS_SCHEMA } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { AppComponent } from './app.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { SafePipe } from './shared/pipes/safe.pipe';
import { AppRoutingModule } from './app-routing.module';
import { AssetDetailComponent, AssetListComponent, LayoutComponent } from './components';
import { MaterialModule } from './shared/material/material.module';
import { AssetDataResolver } from './resolver/asset-data.resolver';
import { SubLocationAddComponent } from '@app/components/modals/sub-location-add/sub-location-add.component';
import { PricePipe } from '@app/pipes/price.pipe';
import { CustomFieldAddComponent } from '@app/components/modals/custom-field-add/custom-field-add.component';
import { FieldPipe } from '@app/pipes/field.pipe';
import { UploadPhotoComponent } from '@app/components/upload-photo/upload-photo.component';
import { FileDropDirective } from '@app/components/upload-photo/file-drop.directive';
import { FloorPipe } from '@app/pipes/floor.pipe';
import { AssetTypePipe } from '@app/pipes/assetType.pipe';
import { NgxGalleryModule } from 'ngx-gallery';
import { SettingComponent } from '@app/components/setting/setting.component';
import { PriceTypeAddComponent } from '@app/components/modals/add-price-type/price-type-add.component';
import { PriceSettingComponent } from '@app/components/setting/price-setting/price-setting.component';
import { CustomFieldSettingComponent } from '@app/components/setting/custom-field-setting/custom-field-setting.component';
import { CustomFieldSettingAddComponent } from '@app/components/modals/custom-field-setting-add/custom-field-setting-add.component';
import { AssetAddAtriumComponent } from '@app/components/asset-add/atrium/asset-add-atrium.component';
import { OrganizationComponent } from './components/setting/organization/organization.component';
import { BuildingComponent } from './components/setting/building/building.component';
import { CustomerComponent } from './components/setting/customer/customer.component';
import { AssetAddVenueComponent } from './components/asset-add/venue/asset-add-venue.component';
import { VenueListBookingComponent } from '@app/components/venue-list-booking/venue-list-booking.component';
import { VenueOrderDetailComponent } from '@app/components/venue-order-detail/venue-order-detail.component';
import { ConfirmationComponent } from '@app/components/modals/confirmation/confirmation.component';


@NgModule({
    declarations: [
        // components
        AppComponent,
        LayoutComponent,
        AssetAddAtriumComponent,
        AssetDetailComponent,
        AssetListComponent,
        SubLocationAddComponent,
        CustomFieldAddComponent,
        ConfirmationComponent,
        UploadPhotoComponent,
        SettingComponent,
        PriceTypeAddComponent,
        PriceSettingComponent,
        CustomFieldSettingComponent,
        CustomFieldSettingAddComponent,
        AssetAddVenueComponent,
        VenueListBookingComponent,
        VenueOrderDetailComponent,
        // pipes
        SafePipe,
        PricePipe,
        FieldPipe,
        FloorPipe,
        AssetTypePipe,

        // directive
        FileDropDirective,

        OrganizationComponent,

        BuildingComponent,

        CustomerComponent
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        ReactiveFormsModule,
        BrowserAnimationsModule,
        HttpClientModule,
        MaterialModule,
        NgxGalleryModule,
        FormsModule
    ],
    providers: [AssetDataResolver],
    bootstrap: [AppComponent],
    entryComponents: [
        SubLocationAddComponent,
        CustomFieldAddComponent,
        PriceTypeAddComponent,
        CustomFieldSettingAddComponent,
        ConfirmationComponent
    ],
    schemas: [NO_ERRORS_SCHEMA]
})
export class AppModule {
}

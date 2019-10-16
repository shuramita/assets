import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AssetDetailComponent, AssetListComponent, LayoutComponent } from './components';
import { AssetDataResolver } from './resolver/asset-data.resolver';
import { SettingComponent } from '@app/components/setting/setting.component';
import { PriceSettingComponent } from '@app/components/setting/price-setting/price-setting.component';
import { CustomFieldSettingComponent } from '@app/components/setting/custom-field-setting/custom-field-setting.component';
import { AssetAddAtriumComponent } from '@app/components/asset-add/atrium/asset-add-atrium.component';
import { VenueHomeComponent } from '@app/components/venue/venue-home/venue-home.component';
import {OrganizationComponent} from '@app/components/setting/organization/organization.component';
import {BuildingComponent} from '@app/components/setting/building/building.component';
import {CustomerComponent} from '@app/components/setting/customer/customer.component';
import { AssetAddVenueComponent } from './components/asset-add/venue/asset-add-venue.component';
import { VenueListBookingComponent } from '@app/components/venue-list-booking/venue-list-booking.component';
import { VenueOrderDetailComponent } from '@app/components/venue-order-detail/venue-order-detail.component';

const routes: Routes = [
    {
        path: '',
        component: LayoutComponent,
        resolve: {
            dataPreparation: AssetDataResolver
        },
        children: [
            {
                path: 'asset',
                children: [
                    { path: '', component: AssetListComponent },
                    {
                        path: 'add',
                        children: [
                            {
                                path: 'atrium-space',
                                component: AssetAddAtriumComponent
                            },
                            {
                                path: 'venue',
                                component: AssetAddVenueComponent
                            }
                        ],
                    },
                    {
                        path: 'edit',
                        children: [
                            {
                                path: 'atrium-space/:id',
                                component: AssetAddAtriumComponent
                            },
                            {
                                path: 'venue/:id',
                                component: AssetAddVenueComponent
                            }
                        ]
                    },
                    
                    { path: 'detail/:id', component: AssetDetailComponent },
                    {
                        path: 'venue',
                        loadChildren:
                        'app/components/venue/venue-layout.module#VenueLayoutModule'
                    },
                    { path: 'venue/bookings', component: VenueListBookingComponent },
                    { path: 'venue/order/detail/:orderId', component: VenueOrderDetailComponent }
                ]
            },
            {
                path: 'asset/setting',
                component: SettingComponent,
                children: [
                    { path: 'price', component: PriceSettingComponent },
                    { path: 'custom-field', component: CustomFieldSettingComponent },
                    { path: 'organization', component: OrganizationComponent },
                    { path: 'building', component: BuildingComponent },
                    { path: 'customer', component: CustomerComponent },
                ]
            }
        ]
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {
}

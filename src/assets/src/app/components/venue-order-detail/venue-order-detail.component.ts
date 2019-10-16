import { Component, OnInit } from '@angular/core';
import { VenueService } from '@app/services/venue.service';
import { ActivatedRoute } from '@angular/router';
import { MatDialog } from '@angular/material';
import { ConfirmationComponent } from '@app/components/modals/confirmation/confirmation.component';

@Component({
  selector: 'app-venue-order-detail',
  templateUrl: './venue-order-detail.component.html',
  styleUrls: ['./venue-order-detail.component.scss']
})
export class VenueOrderDetailComponent implements OnInit {
  orderId;
  orderDetail;

  constructor(
    protected venueService: VenueService,
    private _route: ActivatedRoute,
    protected _matDialog: MatDialog,
  ) { }

  ngOnInit() {
    this.orderId = this._route.snapshot.params['orderId'];
    this.venueService.getBookingDetail(this.orderId)
    .subscribe(res => {
      this.orderDetail = res;
    });
  }

  openConfirmationDialog(action) {
    let data = {};

    if (action === 'reject') {
      data = {
        title: 'Landlord Reject a booking',
        content: 'After rejected a message will sent to user to inform that their booking has been rejected'
      };
    } else if (action === 'approve') {
      data = {
        title: 'Landlord approve a booking',
        content: 'After approved the maker can able to generate the contract base on this booking'
      };
    }

    const dialogRef = this._matDialog.open(ConfirmationComponent, {
      id: action,
      panelClass: 'confirmation',
      width: '50%',
      disableClose: true,
      autoFocus: false,
      data
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result && result.isSubmit) {
        if (dialogRef.id === 'approve') {

          this.venueService.postBookingApprove(this.orderId)
          .subscribe(res => {
            if (res && res.status === 'approved') {
              this.orderDetail.status = 'approved';
            }
          });
        } else if (dialogRef.id === 'reject') {

          this.venueService.postBookingReject(this.orderId)
          .subscribe(res => {
            if (res && res.status === 'rejected') {
              this.orderDetail.status = 'rejected';
            }
            console.log('reject', res)
          });
        }
      }
    });
  }
}

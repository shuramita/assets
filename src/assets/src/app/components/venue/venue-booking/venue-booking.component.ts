import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { EMAIL_REGEXP, IMAGE_URL } from '@app/shared/common/common.constant';
import { VenueService } from '@app/services/venue.service';
import { ActivatedRoute } from '@angular/router';
import { MatStepper } from '@angular/material';
import {HelperService} from '@service/helper.service';

@Component({
  selector: 'app-venue-booking',
  templateUrl: './venue-booking.component.html',
  styleUrls: ['./venue-booking.component.scss']
})
export class VenueBookingComponent implements OnInit {
  @ViewChild('stepper') stepper: MatStepper;
  firstFormGroup: FormGroup;
  secondFormGroup: FormGroup;
  countries = [
    {
      key: 'singapore',
      value: 'Singapore'
    }
  ];
  checkCondition = true;
  venueId;
  venue;
  loading = false;
  IMAGE_URL;
  positionCardNumber;
  positionCardHolder;
  positionExpiryDate;
  constructor(
    private _formBuilder: FormBuilder,
    protected venueService: VenueService,
    private _route: ActivatedRoute,
    public helper: HelperService
  ) {
    this.IMAGE_URL = IMAGE_URL;
  }

  ngOnInit() {
    this.venueId = this._route.snapshot.params['venueId'];
    this.venueService.getVenueDetail(this.venueId).subscribe(res => {
      this.venue = res;
    });

    this.firstFormGroup = this._formBuilder.group({
      firstName: ['', Validators.required],
      lastName: ['', Validators.required],
      email: ['', [Validators.required, Validators.pattern(EMAIL_REGEXP)]],
      phoneNumber: [null, Validators.required],
      countryOfResidence: [this.countries[0].key, []],
      description: ['', []]
    });
    this.secondFormGroup = this._formBuilder.group({
      paymentType: ['creditCard', []],
      cardNumber: [null, []],
      cardHolderName: ['', []],
      expiresDate: ['', []],
      cvv: ['', []],
    });
  }

  setPositionCardInfoOnImage() {
    const ele = document.querySelector('.container');

    if (ele) {
      const clientHeight = ele.clientHeight;
      this.positionCardNumber = {
        offsetX: 0.17 * clientHeight,
        offsetY: 0.53 * clientHeight
      };

      this.positionCardHolder = {
        offsetX: 0.17 * clientHeight,
        offsetY: 0.23 * clientHeight
      };

      this.positionExpiryDate = {
        offsetX: 0.97 * clientHeight,
        offsetY: 0.23 * clientHeight
      };
    }
  }

  setPaymentType(paymentType) {
    this.secondFormGroup.controls['paymentType'].patchValue(paymentType);
    this.secondFormGroup.controls['paymentType'].updateValueAndValidity();
  }

  onSubmit() {
    this.loading = true;
    const firstFormGroup = this.firstFormGroup.getRawValue();
    const secondFormGroup = this.secondFormGroup.getRawValue();
    const dataSubmit = {
      asset_id: this.venueId,
      first_name: firstFormGroup.firstName,
      last_name: firstFormGroup.lastName,
      email: firstFormGroup.email,
      phone_number: firstFormGroup.phoneNumber,
      description: firstFormGroup.description,
      credit_card_number: secondFormGroup.cardNumber,
      card_holder: secondFormGroup.cardHolderName,
      expired_date: secondFormGroup.expiresDate,
      cvv: secondFormGroup.cvv,
    };

    this.venueService.postBooking(dataSubmit)
    .subscribe(res => {
      this.stepper.next();
      this.loading = false;
    }, err => {
      this.loading = false;
    });
  }
}

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VenueListBookingComponent } from './venue-list-booking.component';

describe('VenueListBookingComponent', () => {
  let component: VenueListBookingComponent;
  let fixture: ComponentFixture<VenueListBookingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VenueListBookingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VenueListBookingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

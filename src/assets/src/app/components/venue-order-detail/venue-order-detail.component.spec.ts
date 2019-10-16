import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VenueOrderDetailComponent } from './venue-order-detail.component';

describe('VenueOrderDetailComponent', () => {
  let component: VenueOrderDetailComponent;
  let fixture: ComponentFixture<VenueOrderDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VenueOrderDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VenueOrderDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

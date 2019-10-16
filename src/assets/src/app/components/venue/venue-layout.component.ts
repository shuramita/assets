import { Component, OnInit } from '@angular/core';
import { NavService } from '@app/services/nav.service';
import { MatIconRegistry } from '@angular/material';
import { DomSanitizer } from '@angular/platform-browser';
import { HelperService } from '@service/helper.service';
import { AuthService } from '@app/services/auth.service';

@Component({
  selector: 'app-venue-layout',
  templateUrl: './venue-layout.component.html',
  styleUrls: ['./venue-layout.component.scss']
})
export class VenueLayoutComponent implements OnInit {
  apiKey;
  constructor(
    protected navService: NavService,
    private iconRegistry: MatIconRegistry,
    private sanitizer: DomSanitizer,
    public helper: HelperService,
    protected authService: AuthService
  ) {
    navService.hideNavBar();
    iconRegistry.addSvgIcon(
      'chair',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('chair.svg'))
    );
    iconRegistry.addSvgIcon(
      'sqaure',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('sqaure.svg'))
    );
    iconRegistry.addSvgIcon(
      'visa',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('card-visa-blue.svg'))
    );
    iconRegistry.addSvgIcon(
      'master',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('master.svg'))
    );
    iconRegistry.addSvgIcon(
      'facebook',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('facebook-black.svg'))
    );
    iconRegistry.addSvgIcon(
      'twitter',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('twitter-black.svg'))
    );
    iconRegistry.addSvgIcon(
      'youtube',
      sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('youtube-black.svg'))
    );
  }

  ngOnInit() {
    this.apiKey = document.head.querySelector('meta[name="api-token"]');
  }

  onActivate(event) {
    window.scroll(0, 0);
  }

  logout() {
    this.authService.logout()
      .subscribe(res => {
        window.location.href = res.redirectURL;
      });
  }
}

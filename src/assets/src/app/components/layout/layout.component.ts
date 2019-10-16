import { Component, OnInit } from '@angular/core';
import { NavItem } from '@app/models/nav-item';
import { NavService } from '@service/nav.service';
import { MatIconRegistry } from '@angular/material';
import { DomSanitizer } from '@angular/platform-browser';
import { HelperService } from '@app/services/helper.service';
import { AuthService } from '@app/services/auth.service';

@Component({
    selector: 'app-layout',
    templateUrl: './layout.component.html',
    styleUrls: ['./layout.component.scss'],
})
export class LayoutComponent implements OnInit {
    isShowNavBar: boolean;
    mode: string;
    isExpanded = false;
    notifications = false;
    items: NavItem[] = [];

    constructor(
        private navService: NavService,
        private iconRegistry: MatIconRegistry,
        private sanitizer: DomSanitizer,
        public helper: HelperService,
        protected authService: AuthService
    ) {
        this.mode = 'side';

        iconRegistry.addSvgIcon(
            'app-logo',
            sanitizer.bypassSecurityTrustResourceUrl(this.helper.svg('main-logo.svg'))
        );
    }

    ngOnInit() {
        this.navService.isShowNavBar.subscribe(val => {
            this.isShowNavBar = val;
        });
        this.navService.getItems().subscribe(
            (result) => {
                Object.keys(result).forEach((key) => {
                    this.items.push(new NavItem(result[key].link, result[key].name, result[key].icon));

                });
            }
        );
    }

    logout() {
        this.authService.logout()
            .subscribe(res => {
                window.location.href = res.redirectURL;
            });
    }
}

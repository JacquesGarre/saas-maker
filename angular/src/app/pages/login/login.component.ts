import { Component } from '@angular/core';
import { PublicNavbarComponent } from '../../components/public-navbar/public-navbar.component';
import { Router } from '@angular/router';
import { ToasterConfig } from '../../components/toaster/toaster-config.interface';
import { ToasterComponent } from '../../components/toaster/toaster.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    PublicNavbarComponent,
    ToasterComponent,
    CommonModule
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {

  toasterConfig: ToasterConfig;

  constructor(private router: Router) {
    this.toasterConfig = this.router.getCurrentNavigation()?.extras?.state?.['toasterConfig'] ?? null;
  }
}

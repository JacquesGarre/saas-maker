import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-public-navbar',
  standalone: true,
  imports: [
    CommonModule
  ],
  templateUrl: './public-navbar.component.html',
  styleUrl: './public-navbar.component.scss'
})
export class PublicNavbarComponent {

  userIsLoggedIn: boolean = false;

  constructor(authService: AuthService) {
    this.userIsLoggedIn = authService.isAuthenticated();
  }

}

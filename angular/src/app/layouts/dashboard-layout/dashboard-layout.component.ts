import { Component } from '@angular/core';
import { DashboardNavbarComponent } from '../../components/dashboard-navbar/dashboard-navbar.component';

@Component({
  selector: 'app-dashboard-layout',
  standalone: true,
  imports: [
    DashboardNavbarComponent
  ],
  templateUrl: './dashboard-layout.component.html',
  styleUrl: './dashboard-layout.component.scss'
})
export class DashboardLayoutComponent {

}

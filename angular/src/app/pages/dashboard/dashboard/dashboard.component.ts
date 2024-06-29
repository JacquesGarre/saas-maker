import { Component } from '@angular/core';
import { ApplicationCardComponent } from '../../../components/application-card/application-card.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    ApplicationCardComponent
  ],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.scss'
})
export class DashboardComponent {

}

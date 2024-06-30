import { Component } from '@angular/core';
import { ApplicationCardComponent } from '../../../components/application-card/application-card.component';
import { CreateApplicationCardBtnComponent } from '../../../components/create-application-card-btn/create-application-card-btn.component';
import { CreateApplicationBtnComponent } from '../../../components/create-application-btn/create-application-btn.component';
import { ModalComponent } from '../../../components/modal/modal.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    ApplicationCardComponent,
    CreateApplicationCardBtnComponent,
    CreateApplicationBtnComponent,
    ModalComponent
  ],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.scss'
})
export class DashboardComponent {

}

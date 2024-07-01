import { Component } from '@angular/core';
import { ModalService } from '../../services/modal.service';
import { CreateApplicationFormComponent } from '../create-application-form/create-application-form.component';

@Component({
  selector: 'app-create-application-card-btn',
  standalone: true,
  imports: [
    CreateApplicationFormComponent
  ],
  templateUrl: './create-application-card-btn.component.html',
  styleUrl: './create-application-card-btn.component.scss'
})
export class CreateApplicationCardBtnComponent {

  constructor(private modalService: ModalService){
  }

  openModal(): void {
    this.modalService.open('New application', CreateApplicationFormComponent);
  }
  
}

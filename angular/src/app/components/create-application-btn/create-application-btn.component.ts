import { Component } from '@angular/core';
import { ModalService } from '../../services/modal.service';
import { CreateApplicationFormComponent } from '../create-application-form/create-application-form.component';

@Component({
  selector: 'app-create-application-btn',
  standalone: true,
  imports: [
    CreateApplicationFormComponent
  ],
  templateUrl: './create-application-btn.component.html',
  styleUrl: './create-application-btn.component.scss'
})
export class CreateApplicationBtnComponent {


  constructor(private modalService: ModalService){
  }

  openModal(): void {
    this.modalService.open('New application', CreateApplicationFormComponent);
  }

}

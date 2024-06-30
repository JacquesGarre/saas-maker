import { Component } from '@angular/core';
import { ModalService } from '../../services/modal.service';

@Component({
  selector: 'app-create-application-btn',
  standalone: true,
  imports: [],
  templateUrl: './create-application-btn.component.html',
  styleUrl: './create-application-btn.component.scss'
})
export class CreateApplicationBtnComponent {


  constructor(private modalService: ModalService){
  }

  openModal(): void
  {
    this.modalService.open();
  }

}

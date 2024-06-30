import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from 'rxjs';
import { ModalService } from '../../services/modal.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-modal',
  standalone: true,
  imports: [
    CommonModule,
  ],
  templateUrl: './modal.component.html',
  styleUrls: ['./modal.component.scss']
})

export class ModalComponent implements OnDestroy {

  show: boolean = false;
  private modalSubscription: Subscription | undefined;

  constructor(private modalService: ModalService) {
    this.modalSubscription = this.modalService.showModal$.subscribe(show => {
      this.show = show;
    });
  }

  ngOnDestroy() {
    if (this.modalSubscription) {
      this.modalSubscription.unsubscribe();
    }
  }

  close() {
    this.modalService.close()
  }
}
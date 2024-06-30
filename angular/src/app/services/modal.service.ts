import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ModalService {
  private showModalSubject = new BehaviorSubject<boolean>(false);
  showModal$ = this.showModalSubject.asObservable();

  open() {
    this.showModalSubject.next(true);
  }

  close() {
    this.showModalSubject.next(false);
  }
}
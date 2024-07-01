import { Injectable, Type } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ModalService {
  
  private showModalSubject = new BehaviorSubject<{ show: boolean, title?: string, component?: Type<any> }>({ show: false });
  showModal$ = this.showModalSubject.asObservable();

  open(title: string, component: Type<any>) {
    this.showModalSubject.next({ show: true, title: title, component });
  }

  close() {
    this.showModalSubject.next({ show: false });
  }
}
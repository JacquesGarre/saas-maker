import { Component, OnInit, OnDestroy, ViewChild, ViewContainerRef, ComponentFactoryResolver, AfterViewInit, Input, Type } from '@angular/core';
import { Subscription } from 'rxjs';
import { ModalService } from '../../services/modal.service';
import { CommonModule } from '@angular/common';
import { ComponentLoaderService } from '../../services/component-loader.service';

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

  @ViewChild('container', { read: ViewContainerRef, static: true }) container!: ViewContainerRef;
  show: boolean = false;
  private modalSubscription: Subscription | undefined;
  title: string | undefined;

  constructor(
    private modalService: ModalService, 
    private componentLoader: ComponentLoaderService
  ) {
    this.modalSubscription = this.modalService.showModal$.subscribe(({ show, title, component }) => {
      this.show = show;
      this.title = title;
      if (show && component) {
        this.loadComponent(component);
      } else if(this.container) {
        this.container.clear();
      }
    });
  }

  loadComponent(component: Type<any>) {
    this.componentLoader.loadComponent(this.container, component);
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
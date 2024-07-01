import { Injectable, ComponentFactoryResolver, ViewContainerRef, Type } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class ComponentLoaderService {
  constructor(private resolver: ComponentFactoryResolver) {}

  loadComponent(container: ViewContainerRef, component: Type<any>) {
    const factory = this.resolver.resolveComponentFactory(component);
    container.clear();
    container.createComponent(factory);
  }
}
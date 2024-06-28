import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';
import { ToasterConfig } from './toaster-config.interface';

@Component({
  selector: 'app-toaster',
  standalone: true,
  imports: [
    CommonModule
  ],
  templateUrl: './toaster.component.html',
  styleUrl: './toaster.component.scss'
})
export class ToasterComponent {

  @Input() config!: ToasterConfig;

  close() {
    this.config.show = false;
  }
}

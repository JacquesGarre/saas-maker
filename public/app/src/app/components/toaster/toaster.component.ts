import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

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
  @Input() message: string = '';
  @Input() show: boolean = false;
  @Input() color: string = '';

  close() {
    this.show = false;
  }
}

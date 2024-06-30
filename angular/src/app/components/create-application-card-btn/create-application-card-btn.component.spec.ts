import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateApplicationCardBtnComponent } from './create-application-card-btn.component';

describe('CreateApplicationCardBtnComponent', () => {
  let component: CreateApplicationCardBtnComponent;
  let fixture: ComponentFixture<CreateApplicationCardBtnComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CreateApplicationCardBtnComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreateApplicationCardBtnComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

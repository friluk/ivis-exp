boolean inEllipse = false;
int startSize = 20;
int currentSize = startSize;
int maxSize = startSize * 2;

void setup() {
  size(800, 480);
  stroke(0);
  fill(0);
}

void draw() {
  inEllipse = false;
  background(#EEEEEE);
  fill(#CC0000);
  stroke(#333333);
  strokeWeight(2);

  int x = width/2;
  int y = height/2;

  int d = dist(x,y, mouseX, mouseY);
  int r = currentSize/2;

  if(d < r) {
    inEllipse = true;
  }
  else {
    inEllipse = false;
  }

  if(inEllipse) {
    currentSize = maxSize;
  }
  else {
    inEllipse = false;
    currentSize = startSize;
  }

  ellipse(x,y, currentSize, currentSize);
}
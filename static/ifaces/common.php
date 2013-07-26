<?php

interface Describable {
  
  function description();
  
}

interface Nameable {
  
  function name();
  
}

interface Identifiable {
  
  function id();
  
}

interface Owner {
  
  function stationCount();
  function systemCount();
  
}

interface Positionable {
  
  function position();
  
}
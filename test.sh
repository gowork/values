#!/bin/bash

function spec() {
  ./bin/phpspec run
}

function unit() {
  ./bin/phpunit tests
}

function stan() {
  ./bin/phpstan analyse src examples tests/GenericCases --level=8
}

function psalm() {
  ./bin/psalm
}

function main() {
  case "$1" in
    spec)
      spec
      ;;
    unit)
      unit
      ;;
    stan)
      stan
      ;;
    psalm)
      psalm
      ;;
    *)
      spec && unit && stan && psalm
      ;;
  esac
}

main "$@"

# Sport radar task

## Assumptions

### Business

* Creating & starting match is same action to simplify process
* There is no limitation on scores
* There is no possible for changing rules or scoring process

### Technical

* if no requirements are set for field/data, then no need of any restrictions - allowing standard data without
  limitations
* introduce Event Sourcing
    * Pros
    * Cons
        * more complex solution
* introduce Event Driven Architecture (EDA), as expecting high load over Score board with multiple sources from matches
  to improve Read Model - based on own experience
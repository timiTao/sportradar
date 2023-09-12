# Sport radar task

## Assumptions

### Business

* the board could hold big number of matches
  * Knowing interface class I assume intention of action, not intention to strictly follow it - then I changed interface of scoring
* Creating & starting match is same action to simplify process
* There is no limitation on scores
* There is no possible for changing rules or scoring process
* the score is always set of 2 integer as score
* the scoring by teams are separate events, as consequences in sport games are different (from my own experience)

### Technical

* if no requirements are set for field/data, then no need of any restrictions - allowing standard data without
  limitations
* introduce Event Sourcing
    * Pros
      * events are natural for matches, and could be easily extended 
    * Cons
        * more complex solution
* introduce Event Driven Architecture (EDA), as expecting high load over Score board with multiple sources from matches
  to improve Read Model - based on own experience, the load of data will increase
* Assume order of events is guaranty by infrastructure
* not implementing snapshots
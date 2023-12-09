import React from 'react'

import axios, {isAxiosError} from 'axios'
import { useParams } from 'react-router-dom'
// function oneCollection(collection) {
//     return (
//         <div key={collection.id} className='collection'>
//             <span className='collection-name'>{collection.name}</span>
//             {
//                 collection.IdWorks.length > 0 ?
//                 collection.IdWorks.map(el => (
//                     <WorkOverview WorkId={el.work_id}/>
//                 ))
//                 : (<p>Нет работ в данной коллекции</p>)
//             }
//         </div>
//     )
// }

const Collection = () => {

    const {idCollection} = useParams()

    return (
        <div className='content'>
            <h1>Collection {idCollection}</h1>
        </div>
    )
}

export {Collection}
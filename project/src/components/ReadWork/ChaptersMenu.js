import { Chapter } from './Chapter'

import React, { Component } from 'react'

// Компонент для отображения меню-списка из глав

class ChaptersMenu extends Component {

    // создать 1 элемент списка глав
    createChapterElem = (number, nameChapter, chapterId, workId) => {
        let parameters = [number, nameChapter, chapterId, workId]
            
        return (
            <Chapter key = {number}
            parameters = {parameters}/>
        )
    }

    render() {

        if (this.props.Chapters.length > 0)
        {
            return (
                <>
                    <div className='ReadWork-BigText'>Содержание</div>
                    {
                        this.props.Chapters.map(el => 
                            this.createChapterElem(el.num + 1, el.chapter_name, el.chapter_id, this.props.idWork)
                        )
                    }
                </>
            )
        }
        else
        {
            return (
                <>
                    <div className='ReadWork-BigText'>В данной работе еще ничего не написано</div>
                </>
            )
        }
    }
}

export default ChaptersMenu


// const ChaptersMenu = (props) => {

//     const Chapters = props.Chapter
    
//     console.log(Chapters)
//     // let leng = props.Length

//     return (
//         <>
//             {
//                 Chapters == Array &&
//                 Chapters.map(el => (
//                     <Chapter key={el.num} number={el.num + 1} nameChapter={el.chapter_name} chapterId={el.chapter_id} workId={props.idWork}/>
//                 ))
//             }
//         </>
//     )
// }

// export {ChaptersMenu}

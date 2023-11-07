<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use DOMDocument;
use DOMXPath;
use Sabberworm\CSS\Parser;


class ValidateLevelController extends Controller
{
    use HttpResponses;
    public function __invoke(Request $request, Level $level)
    {
        $correct_answer = $level->expected_output;
        $user_answer = $request->user_answer;
        $course = strtolower($request->course);
        $areEqual = false;

        if($course === 'html'){
            $areEqual = $this->evaluateHTMLCode($correct_answer, $user_answer);
        } elseif ($course === 'css') {
            $areEqual = $this->evaluateCSSCode($correct_answer, $user_answer);
        }

        if($areEqual && !$this->isLevelCompleted($level->id) && $request->user()->hasRole('student')){
            $this->completeLevel($level->id);
        }

        return $this->success(['is_correct' => $areEqual]);
    }

      private function evaluateHTMLCode($correct_answer, $user_answer){
      try {
        $expectedDom = new DOMDocument();
        $userDOM = new DOMDocument();
 
        $expectedDom->loadHTML($correct_answer);
        $userDOM->loadHTML($user_answer);
 
        $expectedHTML = $expectedDom->getElementsByTagName('body')->item(0);
        $userHTML = $userDOM->getElementsByTagName('body')->item(0);
           
        //  if(!$this->compareNodes($expectedHTML, $userHTML)) return false;
        return $this->compareNodes($expectedHTML, $userHTML);
         return true;
      } catch (\Throwable $th) {
         return false;
      }
    }

    private function compareNodes($expectedNode, $userNode){
        // RETURN FALSE IF NODE NAME ARE NOT EQUAL

        if($expectedNode->nodeName !== $userNode->nodeName){
            return false;
        }


        // COMPARE TEXT AND COMMENT NODE
        if($expectedNode->nodeType === XML_TEXT_NODE || $expectedNode->nodeType === XML_COMMENT_NODE){
            $expectedText = trim($expectedNode->textContent);
            $userText = trim($userNode->textContent);
            return $expectedText === $userText;
        }


        $expectedAttributes = $expectedNode->attributes;
        $userAttributes = $userNode->attributes;

        // RETURN FALSE IF ATTRIBUTES LENGTH ARE NOT EQUAL
        if($expectedAttributes->length !== $userAttributes->length){
            return false;
        }


        // COMPARE ATTRIBUTES
        for ($i=0; $i < $expectedAttributes->length; $i++) { 
            $expectedAttribute = $expectedAttributes[$i];
            $userAttribute = $userAttributes[$i];

            if($expectedAttribute->name !== $userAttribute->name) return false;
            if($expectedAttribute->value !== $userAttribute->value) return false;
        }


        $expectedChildNodes = $expectedNode->childNodes;
        $userChildNodes = $userNode->childNodes;
        // RETURN FALSE IF NUMBER OF CHILD NODES ARE NOT EQUAL
        if($expectedChildNodes->length !== $userChildNodes->length){
            return false;
        }


        // COMPARE CHILD NODES
        for ($i=0; $i < $expectedChildNodes->length ; $i++) { 
          $expectedChildNode = $expectedChildNodes[$i];
          $userChildNode = $userChildNodes[$i];
          if(!$this->compareNodes($expectedChildNode, $userChildNode)){
            return false;
          }
        }
        
        return true;
    }

  

    private function evaluateCSSCode($correct_answer, $user_answer){
      if(!$this->evaluateHTMLCode($correct_answer, $user_answer)) return false;
        
        try {
            $userDom = new DOMDocument();
            $expectedDom = new DOMDocument();
            $userDom->loadHTML($user_answer);
            $expectedDom->loadHTML($correct_answer);
    
            $xpathUser = new DOMXPath($userDom);
            $styleNodesUser = $xpathUser->query('//style');
    
            $xpathExpected = new DOMXPath($expectedDom);
            $styleNodesExpected = $xpathExpected->query('//style');
    
            $expectedStyles = $this->parseCSS($styleNodesExpected->item(0)->nodeValue);
            $userStyles = $this->parseCSS($styleNodesUser->item(0)->nodeValue);

            sort($expectedStyles);
            sort($userStyles);

            if(count($expectedStyles) !== count($userStyles)) return false;

            for ($i=0; $i < count($expectedStyles); $i++) { 
                $expectedStyle = $expectedStyles[$i];
                $userStyle = $userStyles[$i];

                if(count($expectedStyle['selectors']) !== count($userStyle['selectors'])) return false;
                if(count($expectedStyle['declarations']) !== count($userStyle['declarations'])) return false;

                if(!empty(array_diff($expectedStyle['selectors'], $userStyle['selectors']))) return false;
                if(!empty(array_diff($expectedStyle['declarations'], $userStyle['declarations']))) return false;
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function parseCSS($css){
        $parser = new Parser($css);
        $cssDocument = $parser->parse();
        $output = [];

        foreach ($cssDocument->getAllRuleSets() as $ruleSet) {
            $selectors = $ruleSet->getSelectors();
            $declarations = [];

            foreach ($ruleSet->getRulesAssoc() as $property => $value) {
                $value = preg_replace('/\s+/', '', $value);
                $declarations[] = $value;
            }

            $selectorText = [];

            foreach($selectors as $selector){
                $selectorText[] = $selector->getSelector();
            }

            $output[] = [
                "selectors" => $selectorText,
                "declarations" => $declarations,
            ];
        }

        return $output;
    }

    private function completeLevel($levelId){
        auth()->user()->levels()->attach($levelId);
    }

    private function isLevelCompleted($levelId){
        $level = auth()->user()->levels->find($levelId);
        return $level ? true: false;
    }
}